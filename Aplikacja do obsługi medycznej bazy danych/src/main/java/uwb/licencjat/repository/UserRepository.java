package uwb.licencjat.repository;

import org.springframework.data.domain.Sort;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;
import uwb.licencjat.model.User;

import java.util.List;
import java.util.Optional;

@Repository
public interface UserRepository extends JpaRepository<User, Long> {

    boolean existsByPeselIgnoreCase(String pesel);
    boolean existsByEmailIgnoreCase(String email);
    Optional<User> findByEmail(String email);
    Optional<User> findByPesel(String email);

    @Query("SELECT u FROM User u WHERE UPPER(u.firstName) LIKE UPPER(CONCAT('%', :name, '%')) OR UPPER(u.lastName) " +
            "LIKE UPPER(CONCAT('%', :lastName, '%')) OR UPPER(u.email) LIKE UPPER(CONCAT('%', :email, '%')) " +
            "OR UPPER(u.pesel) LIKE UPPER(CONCAT('%', :pesel, '%'))")
    List<User> findAllByNameLastnameEmailPeselContaining(
            @Param("name") String name, @Param("lastName") String lastName,
            @Param("email") String email, @Param("pesel") String pesel,Sort sort);
}
